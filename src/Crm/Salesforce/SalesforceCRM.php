<?php
/**
 * Created by PhpStorm.
 * User: Karthik
 * Date: 12/27/2018
 * Time: 3:52 PM
 */

namespace Epro\Crm\Salesforce;

use Epro\Crm\CrmBase;
use Yii;

class SalesforceCRM extends CrmBase
{
    private $api_version = "/services/data/v44.0";

    public function login($username, $password, array $options = [])
    {
        $this->login = $this->curlPost(
            "https://login.salesforce.com/services/oauth2/token",
            array(
                'grant_type' => "password",
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'username' => $username,
                'password' => $password . (isset($options["security_token"]) ? $options["security_token"] : "")
            )
        );
        if (empty($this->login["access_token"])) {
            throw new ForbiddenHttpException(Yii::t('yii', 'Invalid CRM login details.'));
        }
        return $this;
    }

    public function getAccounts(array $options = [])
    {
        $sql = "SELECT Name, Id from Account";
        $accounts = [];
        $records = $this->query($sql);
        if (isset($records["records"])) {
            foreach ($records["records"] as $t) {
                array_push($accounts, [
                    "id" => $t["Id"],
                    "name" => $t["Name"],
                ]);
            }
        }
        return $accounts;
    }

    public function getContacts(array $options = [])
    {
        $sql = "SELECT Name, Id from Contact";
        $contacts = [];
        $records = $this->query($sql);
        if (isset($records["records"])) {
            foreach ($records["records"] as $t) {
                array_push($contacts, [
                    "id" => $t["Id"],
                    "name" => $t["Name"],
                ]);
            }
        }
        return $contacts;
    }

    public function getLeads(array $options = [])
    {
        $sql = "SELECT Name, Id from Lead";
        $leads = [];
        $records = $this->query($sql);
        if (isset($records["records"])) {
            foreach ($records["records"] as $t) {
                array_push($leads, [
                    "id" => $t["Id"],
                    "name" => $t["Name"],
                ]);
            }
        }
        return $leads;
    }

    public function getMeetings(array $options = [])
    {
        $sql = " SELECT ";
        $sql .= " Id, Subject, StartDateTime, EndDateTime ";
        $sql .= " FROM Event ";
        $meetings = [];
        $records = $this->query($sql);
        if (isset($records["records"])) {
            foreach ($records["records"] as $t) {
                array_push($meetings, [
                    "id" => $t["Id"],
                    "name" => $t["Subject"],
                    "start_dt" => $t["StartDateTime"],
                    "end_dt" => $t["EndDateTime"],
                ]);
            }
        }
        return $meetings;
    }

    private function query($sql, array $options = [])
    {
        return $this->curlGet(
            "{$this->url}{$this->api_version}/query?q=" . urlencode($sql),
            array("Authorization: OAuth {$this->login["access_token"]}")
        );
    }
}

/**
 * Salesforce objects:
 *
 * [
 * "AcceptedEventRelation",
 * "Account",
 * "AccountChangeEvent",
 * "AccountCleanInfo",
 * "AccountContactRole",
 * "AccountContactRoleChangeEvent",
 * "AccountFeed",
 * "AccountHistory",
 * "AccountPartner",
 * "AccountShare",
 * "ActionLinkGroupTemplate",
 * "ActionLinkTemplate",
 * "ActivityHistory",
 * "AdditionalNumber",
 * "AggregateResult",
 * "Announcement",
 * "ApexClass",
 * "ApexComponent",
 * "ApexEmailNotification",
 * "ApexLog",
 * "ApexPage",
 * "ApexPageInfo",
 * "ApexTestQueueItem",
 * "ApexTestResult",
 * "ApexTestResultLimits",
 * "ApexTestRunResult",
 * "ApexTestSuite",
 * "ApexTrigger",
 * "AppDefinition",
 * "AppMenuItem",
 * "AppTabMember",
 * "Asset",
 * "AssetChangeEvent",
 * "AssetFeed",
 * "AssetHistory",
 * "AssetRelationship",
 * "AssetRelationshipFeed",
 * "AssetRelationshipHistory",
 * "AssetShare",
 * "AssetTokenEvent",
 * "AssignmentRule",
 * "AsyncApexJob",
 * "AttachedContentDocument",
 * "Attachment",
 * "AuraDefinition",
 * "AuraDefinitionBundle",
 * "AuraDefinitionBundleInfo",
 * "AuraDefinitionInfo",
 * "AuthConfig",
 * "AuthConfigProviders",
 * "AuthProvider",
 * "AuthSession",
 * "BackgroundOperation",
 * "BatchApexErrorEvent",
 * "BrandTemplate",
 * "BrandingSet",
 * "BrandingSetProperty",
 * "BusinessHours",
 * "BusinessProcess",
 * "CallCenter",
 * "Campaign",
 * "CampaignChangeEvent",
 * "CampaignFeed",
 * "CampaignHistory",
 * "CampaignMember",
 * "CampaignMemberStatus",
 * "CampaignShare",
 * "Case",
 * "CaseChangeEvent",
 * "CaseComment",
 * "CaseContactRole",
 * "CaseFeed",
 * "CaseHistory",
 * "CaseShare",
 * "CaseSolution",
 * "CaseStatus",
 * "CaseTeamMember",
 * "CaseTeamRole",
 * "CaseTeamTemplate",
 * "CaseTeamTemplateMember",
 * "CaseTeamTemplateRecord",
 * "CategoryData",
 * "CategoryNode",
 * "ChatterActivity",
 * "ChatterExtension",
 * "ChatterExtensionConfig",
 * "ClientBrowser",
 * "CollaborationGroup",
 * "CollaborationGroupFeed",
 * "CollaborationGroupMember",
 * "CollaborationGroupMemberRequest",
 * "CollaborationGroupRecord",
 * "CollaborationInvitation",
 * "ColorDefinition",
 * "CombinedAttachment",
 * "Community",
 * "ConnectedApplication",
 * "Contact",
 * "ContactChangeEvent",
 * "ContactCleanInfo",
 * "ContactFeed",
 * "ContactHistory",
 * "ContactShare",
 * "ContentAsset",
 * "ContentBody",
 * "ContentDistribution",
 * "ContentDistributionView",
 * "ContentDocument",
 * "ContentDocumentFeed",
 * "ContentDocumentHistory",
 * "ContentDocumentLink",
 * "ContentDocumentSubscription",
 * "ContentFolder",
 * "ContentFolderItem",
 * "ContentFolderLink",
 * "ContentFolderMember",
 * "ContentNotification",
 * "ContentTagSubscription",
 * "ContentUserSubscription",
 * "ContentVersion",
 * "ContentVersionComment",
 * "ContentVersionHistory",
 * "ContentVersionRating",
 * "ContentWorkspace",
 * "ContentWorkspaceDoc",
 * "ContentWorkspaceMember",
 * "ContentWorkspacePermission",
 * "ContentWorkspaceSubscription",
 * "Contract",
 * "ContractContactRole",
 * "ContractFeed",
 * "ContractHistory",
 * "ContractStatus",
 * "CorsWhitelistEntry",
 * "CronJobDetail",
 * "CronTrigger",
 * "CspTrustedSite",
 * "CustomBrand",
 * "CustomBrandAsset",
 * "CustomHttpHeader",
 * "CustomObjectUserLicenseMetrics",
 * "CustomPermission",
 * "CustomPermissionDependency",
 * "DandBCompany",
 * "Dashboard",
 * "DashboardComponent",
 * "DashboardComponentFeed",
 * "DashboardFeed",
 * "DataAssessmentFieldMetric",
 * "DataAssessmentMetric",
 * "DataAssessmentValueMetric",
 * "DataStatistics",
 * "DataType",
 * "DatacloudAddress",
 * "DatacloudCompany",
 * "DatacloudContact",
 * "DatacloudDandBCompany",
 * "DatacloudOwnedEntity",
 * "DatacloudPurchaseUsage",
 * "DatasetExport",
 * "DatasetExportEvent",
 * "DatasetExportPart",
 * "DeclinedEventRelation",
 * "Document",
 * "DocumentAttachmentMap",
 * "Domain",
 * "DomainSite",
 * "DuplicateRecordItem",
 * "DuplicateRecordSet",
 * "DuplicateRule",
 * "EmailCapture",
 * "EmailDomainKey",
 * "EmailMessage",
 * "EmailMessageRelation",
 * "EmailServicesAddress",
 * "EmailServicesFunction",
 * "EmailStatus",
 * "EmailTemplate",
 * "EmbeddedServiceDetail",
 * "EmbeddedServiceLabel",
 * "EntityDefinition",
 * "EntityParticle",
 * "EntitySubscription",
 * "Event", //Fields: ["Id","WhoId","WhatId","Subject","Location","IsAllDayEvent","ActivityDateTime","ActivityDate","DurationInMinutes","StartDateTime","EndDateTime","Description","AccountId","OwnerId","IsPrivate","ShowAs","IsDeleted","IsChild","IsGroupEvent","GroupEventType","CreatedDate","CreatedById","LastModifiedDate","LastModifiedById","SystemModstamp","IsArchived","RecurrenceActivityId","IsRecurrence","RecurrenceStartDateTime","RecurrenceEndDateOnly","RecurrenceTimeZoneSidKey","RecurrenceType","RecurrenceInterval","RecurrenceDayOfWeekMask","RecurrenceDayOfMonth","RecurrenceInstance","RecurrenceMonthOfYear","ReminderDateTime","IsReminderSet","EventSubtype","IsRecurrence2Exclusion","Recurrence2PatternText","Recurrence2PatternVersion","IsRecurrence2","IsRecurrence2Exception","Recurrence2PatternStartDate","Recurrence2PatternTimeZone"]
 * "EventBusSubscriber",
 * "EventChangeEvent",
 * "EventFeed",
 * "EventLogFile",
 * "EventRelation",
 * "EventRelationChangeEvent",
 * "ExternalDataSource",
 * "ExternalDataUserAuth",
 * "FeedAttachment",
 * "FeedComment",
 * "FeedItem",
 * "FeedLike",
 * "FeedPollChoice",
 * "FeedPollVote",
 * "FeedRevision",
 * "FeedSignal",
 * "FeedTrackedChange",
 * "FieldDefinition",
 * "FieldPermissions",
 * "FileSearchActivity",
 * "FiscalYearSettings",
 * "FlexQueueItem",
 * "FlowInterview",
 * "FlowInterviewShare",
 * "FlowRecordRelation",
 * "FlowStageRelation",
 * "Folder",
 * "FolderedContentDocument",
 * "ForecastShare",
 * "ForecastingShare",
 * "GrantedByLicense",
 * "Group",
 * "GroupMember",
 * "Holiday",
 * "IconDefinition",
 * "Idea",
 * "IdeaComment",
 * "IdpEventLog",
 * "InstalledMobileApp",
 * "KnowledgeableUser",
 * "Lead",
 * "LeadChangeEvent",
 * "LeadCleanInfo",
 * "LeadFeed",
 * "LeadHistory",
 * "LeadShare",
 * "LeadStatus",
 * "LightningComponentBundle",
 * "LightningComponentResource",
 * "LightningComponentTag",
 * "LightningExitByPageMetrics",
 * "LightningExperienceTheme",
 * "LightningToggleMetrics",
 * "LightningUsageByAppTypeMetrics",
 * "LightningUsageByBrowserMetrics",
 * "LightningUsageByFlexiPageMetrics",
 * "LightningUsageByPageMetrics",
 * "ListEmail",
 * "ListEmailChangeEvent",
 * "ListEmailIndividualRecipient",
 * "ListEmailRecipientSource",
 * "ListEmailShare",
 * "ListView",
 * "ListViewChart",
 * "ListViewChartInstance",
 * "LoginGeo",
 * "LoginHistory",
 * "LoginIp",
 * "LookedUpFromActivity",
 * "Macro",
 * "MacroHistory",
 * "MacroInstruction",
 * "MacroShare",
 * "MailmergeTemplate",
 * "MatchingRule",
 * "MatchingRuleItem",
 * "Name",
 * "NamedCredential",
 * "Note",
 * "NoteAndAttachment",
 * "OauthToken",
 * "ObjectPermissions",
 * "OnboardingMetrics",
 * "OpenActivity",
 * "Opportunity",
 * "OpportunityChangeEvent",
 * "OpportunityCompetitor",
 * "OpportunityContactRole",
 * "OpportunityContactRoleChangeEvent",
 * "OpportunityFeed",
 * "OpportunityFieldHistory",
 * "OpportunityHistory",
 * "OpportunityLineItem",
 * "OpportunityPartner",
 * "OpportunityShare",
 * "OpportunityStage",
 * "Order",
 * "OrderChangeEvent",
 * "OrderFeed",
 * "OrderHistory",
 * "OrderItem",
 * "OrderItemChangeEvent",
 * "OrderItemFeed",
 * "OrderItemHistory",
 * "OrderShare",
 * "OrgDeleteRequest",
 * "OrgDeleteRequestShare",
 * "OrgLifecycleNotification",
 * "OrgWideEmailAddress",
 * "Organization",
 * "OutgoingEmail",
 * "OutgoingEmailRelation",
 * "OwnedContentDocument",
 * "OwnerChangeOptionInfo",
 * "PackageLicense",
 * "Partner",
 * "PartnerRole",
 * "Period",
 * "PermissionSet",
 * "PermissionSetAssignment",
 * "PermissionSetLicense",
 * "PermissionSetLicenseAssign",
 * "PicklistValueInfo",
 * "PlatformAction",
 * "PlatformCachePartition",
 * "PlatformCachePartitionType",
 * "Pricebook2",
 * "Pricebook2History",
 * "PricebookEntry",
 * "ProcessDefinition",
 * "ProcessInstance",
 * "ProcessInstanceHistory",
 * "ProcessInstanceNode",
 * "ProcessInstanceStep",
 * "ProcessInstanceWorkitem",
 * "ProcessNode",
 * "Product2",
 * "Product2ChangeEvent",
 * "Product2Feed",
 * "Product2History",
 * "Profile",
 * "Publisher",
 * "PushTopic",
 * "QueueSobject",
 * "QuickText",
 * "QuickTextHistory",
 * "QuickTextShare",
 * "QuoteTemplateRichTextData",
 * "RecentlyViewed",
 * "RecordAction",
 * "RecordActionHistory",
 * "RecordType",
 * "RelationshipDomain",
 * "RelationshipInfo",
 * "Report",
 * "ReportFeed",
 * "SamlSsoConfig",
 * "Scontrol",
 * "SearchActivity",
 * "SearchLayout",
 * "SearchPromotionRule",
 * "SecureAgent",
 * "SecureAgentPlugin",
 * "SecureAgentPluginProperty",
 * "SecureAgentsCluster",
 * "SecurityCustomBaseline",
 * "SessionPermSetActivation",
 * "SetupAuditTrail",
 * "SetupEntityAccess",
 * "Site",
 * "SiteFeed",
 * "SiteHistory",
 * "SiteIframeWhiteListUrl",
 * "Solution",
 * "SolutionFeed",
 * "SolutionHistory",
 * "SolutionStatus",
 * "Stamp",
 * "StampAssignment",
 * "StaticResource",
 * "StreamingChannel",
 * "StreamingChannelShare",
 * "TabDefinition",
 * "Task",
 * "TaskChangeEvent",
 * "TaskFeed",
 * "TaskPriority",
 * "TaskStatus",
 * "TenantUsageEntitlement",
 * "TestSuiteMembership",
 * "ThirdPartyAccountLink",
 * "TodayGoal",
 * "TodayGoalShare",
 * "Topic",
 * "TopicAssignment",
 * "TopicFeed",
 * "TopicUserEvent",
 * "TransactionSecurityPolicy",
 * "UndecidedEventRelation",
 * "User",
 * "UserAppInfo",
 * "UserAppMenuCustomization",
 * "UserAppMenuCustomizationShare",
 * "UserAppMenuItem",
 * "UserChangeEvent",
 * "UserEmailPreferredPerson",
 * "UserEmailPreferredPersonShare",
 * "UserEntityAccess",
 * "UserFeed",
 * "UserFieldAccess",
 * "UserLicense",
 * "UserListView",
 * "UserListViewCriterion",
 * "UserLogin",
 * "UserPackageLicense",
 * "UserPermissionAccess",
 * "UserPreference",
 * "UserProvAccount",
 * "UserProvAccountStaging",
 * "UserProvMockTarget",
 * "UserProvisioningConfig",
 * "UserProvisioningLog",
 * "UserProvisioningRequest",
 * "UserProvisioningRequestShare",
 * "UserRecordAccess",
 * "UserRole",
 * "UserShare",
 * "VerificationHistory",
 * "VisualforceAccessMetrics",
 * "Vote",
 * "WaveAutoInstallRequest",
 * "WaveCompatibilityCheckItem",
 * "WebLink",
 * "sale__ChangeEvent",
 * "sale__Feed",
 * "sale__c"
 * ]
 */